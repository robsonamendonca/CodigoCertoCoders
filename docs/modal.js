
document.addEventListener('DOMContentLoaded', () => {
  const modal = document.getElementById('modalPrivacidade');
  const abrir = document.getElementById('abrirModal');
  const fechar = document.querySelector('.fechar');
  const btnCiente = document.getElementById('btnCiente');

  abrir.addEventListener('click', () => {
    modal.style.display = 'flex';
  });

  fechar.addEventListener('click', () => {
    modal.style.display = 'none';
  });

  btnCiente.addEventListener('click', () => {
    modal.style.display = 'none';
    // Aqui você pode salvar o aceite em localStorage se quiser
  });

  window.addEventListener('click', (e) => {
    if (e.target === modal) {
      modal.style.display = 'none';
    }
  });

  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
      modal.style.display = 'none';
    }
  });
});
