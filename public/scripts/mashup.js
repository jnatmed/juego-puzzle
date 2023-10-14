const apiKey = '0eypzDwLPW6u3xErtKE5opLWC4gr14WO_6d4FpGN9Hk'; // Reemplaza con tu API Key de Unsplash
const imageGrid = document.getElementById('image-grid');
const loadImagesButton = document.getElementById('load-images');
let previousDiv = null; // Variable para almacenar el div anterior

loadImagesButton.addEventListener('click', () => {
    fetch(`https://api.unsplash.com/photos/random?client_id=${apiKey}&count=12`)
        .then(response => response.json())
        .then(data => {
            imageGrid.innerHTML = '';

            let contImg = 0;
            data.forEach(photo => {
                const imageCard = document.createElement('div');
                imageCard.classList.add('image-card');
                // imageCard.id = contImg++;

                const image = document.createElement('img');
                image.id = contImg++;
                image.src = photo.urls.regular;
                image.alt = photo.alt_description;

                image.onclick = () => {




                    // Almacena el div actual como el div anterior
                    // previousDiv = image;                    

                    // document.body.appendChild(div); 
                };

                imageCard.appendChild(image);
                imageGrid.appendChild(imageCard);
            });

            const footer = document.querySelector('footer');

            console.log(footer);
            // Establece las propiedades de estilo CSS.
            footer.style.position = 'fixed';
            footer.style.bottom = '0';
            footer.style.width = '100%';
            footer.style.backgroundColor = '#333';
            footer.style.color = '#fff';
            footer.style.padding = '10px';
            footer.style.textAlign = 'center';            
        })
        .catch(error => {
            console.error('Error al cargar imágenes desde Unsplash:', error);
        });
});
