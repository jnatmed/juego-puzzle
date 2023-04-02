const canvas = document.getElementById("miLienzo"),
  ctx = canvas.getContext("2d"),
  image = new Image();

// Wait for the sprite sheet to load
image.onload = () => {
  Promise.all([
    // Cut out two sprites from the sprite sheet
    createImageBitmap(image, 0, 0, 800, 800)
    // createImageBitmap(image, 800, 0, 800, 800),
  ]).then((sprites) => {
    // Draw each sprite onto the canvas
    ctx.drawImage(sprites[0], 1200, 2000);
    // ctx.drawImage(sprites[1], 800, 800);
  });
};

// Load the sprite sheet from an image file
image.src = "imgs\/noticia_normal.jpg";

