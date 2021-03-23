const  FiltersForm = document.querySelector('#filters');

//on boucle sur les input categories
document.querySelectorAll(".category-filter [type=checkbox]").forEach(input => {
   input.addEventListener("change", () => {
       //ici on intercept les click
       //on recupere les données du formulaire
       const Form = new FormData(FiltersForm);

       //on fabrique la queryString
       const Params = new URLSearchParams();

       Form.forEach((value, key) => {
           Params.append(key, value);
       })

       //on recupere l'url active
       const url = new URL(window.location.href);
       console.log(url)
       //on lance la requete ajax
       fetch(url.pathname + "?" + Params.toString() + "&ajax=1", {
           headers: {
               "X-Requested-With" : "XMLHttpRequest"
           }
       }).then(response => {
           console.log(response)
       }).catch(e => alert(e));
   })
});
