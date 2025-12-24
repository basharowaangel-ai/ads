document.getElementById("add-file").addEventListener("change", function (e) {
  const file = e.target.files[0];
  if (file) {
    const reader = new FileReader();
    reader.onload = function (e) {
      const img = document.querySelector(".file-input-label img");
      img.src = e.target.result;
      img.style.maxWidth = "200px";
      img.style.maxHeight = "200px";
    };
    reader.readAsDataURL(file);
  }
});
