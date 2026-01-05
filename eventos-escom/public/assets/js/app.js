(() => {
  const root = document.documentElement;

  // =========================
  //  TEMA (claro/oscuro)
  // =========================
  const temaGuardado = localStorage.getItem("tema");
  if (temaGuardado === "claro") root.dataset.tema = "claro";

  function alternarTema() {
    const esClaro = root.dataset.tema === "claro";
    if (esClaro) {
      delete root.dataset.tema;
      localStorage.setItem("tema", "oscuro");
    } else {
      root.dataset.tema = "claro";
      localStorage.setItem("tema", "claro");
    }
  }

  document.getElementById("btnTema")?.addEventListener("click", alternarTema);
  document.getElementById("btnTemaMini")?.addEventListener("click", alternarTema);

  // =========================
  //  SCROLL ARRIBA
  // =========================
  document.getElementById("btnScrollTop")?.addEventListener("click", () => {
    window.scrollTo({ top: 0, behavior: "smooth" });
  });

  // =========================
  //  NORMALIZACIÓN DE TEXTO
  //  (minúsculas + sin acentos + sin dobles espacios)
  // =========================
  function normalizar(valor) {
    return (valor || "")
      .toString()
      .toLowerCase()
      .normalize("NFD")
      .replace(/[\u0300-\u036f]/g, "") // quita acentos
      .replace(/\s+/g, " ")
      .trim();
  }

  // =========================
  //  BUSCADOR + FILTRO (HOME)
  // =========================
  const buscador = document.getElementById("buscadorEventos");
  const filtro = document.getElementById("filtroCategoria");
  const contenedor = document.getElementById("contenedorTarjetas");
  const estadoSin = document.getElementById("estadoSinResultados");

  function aplicarFiltros() {
    if (!contenedor) return;

    const texto = normalizar(buscador?.value);
    const categoriaSeleccionada = normalizar(filtro?.value);

    const tarjetas = Array.from(contenedor.querySelectorAll(".tarjeta"));
    let visibles = 0;

    for (const t of tarjetas) {
      const titulo = normalizar(t.dataset.titulo);
      const categoria = normalizar(t.dataset.categoria);

      // match por texto: incluye
      const okTexto = texto === "" || titulo.includes(texto);

      // match por categoría: igualdad (ya normalizada)
      const okCat = categoriaSeleccionada === "" || categoria === categoriaSeleccionada;

      const mostrar = okTexto && okCat;
      t.style.display = mostrar ? "" : "none";
      if (mostrar) visibles++;
    }


        // Re-animar las visibles (solo en HOME)
    const visiblesAhora = Array.from(contenedor.querySelectorAll(".tarjeta"))
      .filter(t => t.style.display !== "none");

    visiblesAhora.forEach(t => {
      t.classList.remove("visible");
      // cascada rápida
      setTimeout(() => t.classList.add("visible"), 10);
    });

    if (estadoSin) estadoSin.style.display = visibles === 0 ? "" : "none";
  }

  
  // Asegura que se aplique también al cargar
  aplicarFiltros();
  

  buscador?.addEventListener("input", aplicarFiltros);
  filtro?.addEventListener("change", aplicarFiltros);

  // =========================
  //  COMPARTIR (DETALLE)
  // =========================
  const btnCompartir = document.getElementById("btnCompartir");
  if (btnCompartir) {
    btnCompartir.addEventListener("click", async () => {
      try {
        await navigator.clipboard.writeText(window.location.href);
        const original = btnCompartir.textContent;
        btnCompartir.textContent = "Copiado ✓";
        setTimeout(() => (btnCompartir.textContent = original), 1200);
      } catch {
        alert("No se pudo copiar el enlace. Copia manualmente la URL.");
      }
    });
  }

  // =========================
  //  CALENDARIO (ICS)
  // =========================
  function pad2(n) {
    return String(n).padStart(2, "0");
  }

  function toIcsLocal(fecha, hora) {
    const h = hora && hora.includes(":") ? hora : "12:00";
    const [Y, M, D] = fecha.split("-").map(Number);
    const [hh, mm] = h.split(":").map(Number);

    return `${Y}${pad2(M)}${pad2(D)}T${pad2(hh)}${pad2(mm)}00`;
  }

  function escIcs(texto = "") {
    return String(texto)
      .replace(/\\/g, "\\\\")
      .replace(/\n/g, "\\n")
      .replace(/,/g, "\\,")
      .replace(/;/g, "\\;");
  }

  function descargarIcs({ titulo, descripcion, lugar, fecha, hora }) {
    if (!fecha || !/^\d{4}-\d{2}-\d{2}$/.test(fecha)) {
      alert("Fecha inválida para generar el calendario.");
      return;
    }

    const inicio = toIcsLocal(fecha, hora);

    // Duración default: 2 horas
    const fin = (() => {
      const h = hora && hora.includes(":") ? hora : "12:00";
      const [Y, M, D] = fecha.split("-").map(Number);
      const [hh, mm] = h.split(":").map(Number);
      const start = new Date(Y, M - 1, D, hh, mm, 0);
      const end = new Date(start.getTime() + 2 * 60 * 60 * 1000);
      return `${end.getFullYear()}${pad2(end.getMonth() + 1)}${pad2(end.getDate())}T${pad2(end.getHours())}${pad2(end.getMinutes())}00`;
    })();

    const uid = `${Date.now()}-${Math.random().toString(16).slice(2)}@eventos-escom`;
    const dtstamp = (() => {
      const d = new Date();
      return `${d.getUTCFullYear()}${pad2(d.getUTCMonth() + 1)}${pad2(d.getUTCDate())}T${pad2(d.getUTCHours())}${pad2(d.getUTCMinutes())}${pad2(d.getUTCSeconds())}Z`;
    })();

    const ics =
`BEGIN:VCALENDAR
VERSION:2.0
PRODID:-//Eventos ESCOM//Calendario//ES
CALSCALE:GREGORIAN
METHOD:PUBLISH
BEGIN:VEVENT
UID:${uid}
DTSTAMP:${dtstamp}
SUMMARY:${escIcs(titulo || "Evento")}
DESCRIPTION:${escIcs(descripcion || "")}
LOCATION:${escIcs(lugar || "")}
DTSTART:${inicio}
DTEND:${fin}
END:VEVENT
END:VCALENDAR`;

    const blob = new Blob([ics], { type: "text/calendar;charset=utf-8" });
    const url = URL.createObjectURL(blob);

    const a = document.createElement("a");
    a.href = url;
    a.download = `${(titulo || "evento").toLowerCase().replace(/[^a-z0-9]+/gi, "-").replace(/(^-|-$)/g, "")}.ics`;
    document.body.appendChild(a);
    a.click();
    a.remove();

    setTimeout(() => URL.revokeObjectURL(url), 1500);
  }

  const btnCalendario = document.getElementById("btnCalendario");
  if (btnCalendario) {
    btnCalendario.addEventListener("click", () => {
      const data = btnCalendario.dataset;
      descargarIcs({
        titulo: data.titulo || "Evento",
        descripcion: data.descripcion || "",
        lugar: data.lugar || "",
        fecha: data.fecha || "",
        hora: data.hora || ""
      });

      const original = btnCalendario.textContent;
      btnCalendario.textContent = "Listo ✓";
      setTimeout(() => (btnCalendario.textContent = original), 1200);
    });
  }
    // =========================
  //  ANIMACIÓN POR SECCIONES
  // =========================
  function marcarSecciones() {
    // hero + secciones principales
    document.querySelectorAll(".hero, .seccion").forEach((el) => {
      el.classList.add("anim-seccion");
    });

    // tarjetas
    document.querySelectorAll(".tarjeta").forEach((el) => {
      el.classList.add("anim-item");
    });
  }

  function activarAnimaciones() {
    const secciones = document.querySelectorAll(".anim-seccion");
    const items = document.querySelectorAll(".anim-item");

    const ioSecciones = new IntersectionObserver((entries) => {
      for (const e of entries) {
        if (e.isIntersecting) {
          e.target.classList.add("visible");
          ioSecciones.unobserve(e.target);
        }
      }
    }, { threshold: 0.12 });

    secciones.forEach((s) => ioSecciones.observe(s));

    // Cascada para tarjetas: al entrar en viewport, animación escalonada
    const ioItems = new IntersectionObserver((entries) => {
      const visibles = entries.filter((e) => e.isIntersecting).map((e) => e.target);
      if (visibles.length) {
        visibles.forEach((el, i) => {
          setTimeout(() => el.classList.add("visible"), i * 60);
        });
      }
    }, { threshold: 0.12 });

    items.forEach((it) => ioItems.observe(it));
  }

  // Ejecutar al cargar
  marcarSecciones();
  activarAnimaciones();

})();
