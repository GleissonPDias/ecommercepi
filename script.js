function mostrarCampos() {
  document.getElementById("dropdown").style.display = "flex";
}

function esconderCampos() {
  document.getElementById("dropdown").style.display = "none";
}

const dropdown = () => {
  if (document.getElementById("dropdown").style.display === "flex")
    esconderCampos();
  else mostrarCampos();
};
