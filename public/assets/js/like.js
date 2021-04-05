//Logique de controle des Likes

function onClickBtnLike(event){
    //on évite de suivre le lien
    event.preventDefault();
    //$this est le a sur lequel on clique
    const url = this.href ;
    const spanCount = this.querySelector('span.js-likes');
    //const tooltip = this.querySelector('.tooltip');
    const icone = this.querySelector('i');

    axios.get(url).then(function (response) {
        //actualise mon compteur
        spanCount.textContent = response.data.likes;
        //changer l'état de l'icone
        if(icone.classList.contains('fas')) icone.classList.replace('fas','far');
        else icone.classList.replace('far','fas');
    }).catch(function(error) {
       if(error.response.status === 403) {
           document.querySelectorAll('.js-like').forEach( el => {
             el.classList.add('tooltip');
           })
       }else {
           window.alert("Ooops ! Une erreur s'est produite!")
       }
    });
}

document.querySelectorAll('a.js-like').forEach(function(link){
    link.addEventListener('click', onClickBtnLike);
})