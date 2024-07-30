const likes = document.querySelectorAll(".like")
likes.forEach(like => {
    like.addEventListener('click', (e) => {
        const dataId = like.dataset.id
        console.log('click');
        fetch(`article/like/${dataId}`, {
            method: "POST"
        })
            .then(rep => {
                return rep.json()
            })
            .then(res => {
                console.log(res);
                showLike(res)
            })
        /**
         * Mettre à jour dynamiquement les likes
         * @param {Object} data - Un objet contenant les informations des likes
         */
        function showLike(data) {
            // Trouver l'élément correspondant à l'id dans les données
            const likeElement = document.querySelector(`.like[data-id="${dataId}"]`);
            if (likeElement) {
                likeElement.innerHTML = `
                        <img src="images/blog/heart${(data.liked === true)? "-1": "" }.png" alt="icon d'un coeur">
                        <p>${ data.like.length } </p>
                    `;
            } else {
                console.error(`Aucun élément trouvé avec l'id ${data.id}.`);
            }
        }
    })
})