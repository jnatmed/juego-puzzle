const apiKey = '0eypzDwLPW6u3xErtKE5opLWC4gr14WO_6d4FpGN9Hk'; // Reemplaza con tu API Key de Unsplash
const imageGrid = document.getElementById('image-grid');
const loadImagesButton = document.getElementById('load-images');

loadImagesButton.addEventListener('click', () => {
    fetch(`https://api.unsplash.com/photos/random?client_id=${apiKey}&count=12`)
        .then(response => response.json())
        .then(data => {
            imageGrid.innerHTML = '';

            data.forEach(photo => {
                const imageCard = document.createElement('div');
                imageCard.classList.add('image-card');

                const image = document.createElement('img');
                image.src = photo.urls.regular;
                image.alt = photo.alt_description;

                imageCard.appendChild(image);
                imageGrid.appendChild(imageCard);
            });
        })
        .catch(error => {
            console.error('Error al cargar imágenes desde Unsplash:', error);
        });
});
