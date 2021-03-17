;(function ($) {
  // ----------------------------------------------------------------------------
  // Teste la résence d'un cookie
  // ----------------------------------------------------------------------------
  function getCookie(name) {
    var value = '; ' + document.cookie
    var parts = value.split('; ' + name + '=')
    if (parts.length == 2) return parts.pop().split(';').shift()
    else return null
  }

  // ----------------------------------------------------------------------------
  // Permet d'incrémenter le champ Carbon Field crb_post_likes (cf functions.php)
  // au clic sur le bouton Like (le bouton inône coeur) à l'aide d'une requête Ajax
  // en cas de succès, le serveur renvoie une variable response
  // qui permet de mettre à jour l'affichage du nombre de likes
  // https://developer.wordpress.org/reference/hooks/wp_ajax_action/
  // ----------------------------------------------------------------------------

  $('.fimp_like').click(function (event) {
    event.preventDefault() // Neutralise l'effet du clic sur l'ancre

    // ----------------------------------------------------------------------------
    // On initialise les variables à passer dans la requête ajax
    // ----------------------------------------------------------------------------
    var post_id = $(this).data('postid') // Id du post
    var likes_count = $(this).text() // Valeur du nombre de like (le template affiche 0 si elle n'est pas définie)

    // ----------------------------------------------------------------------------
    // On évalue la présence d'id de l'article dans le cookie 'alreadyliked'
    // ----------------------------------------------------------------------------

    var currentCookieValue = getCookie('alreadyexist') // string
    var newCookieValue = ''
    var alreadyLiked = false
    var doAjax = false

    // Si le cookie est défini, on vérifie que la post_id n'est pas déjà présent avant de l'ajouter
    if (currentCookieValue) {
      // post_id est il déjà dans le cookie ?
      currentCookieValue.split(',').forEach(function (value) {
        if (value == post_id) {
          alreadyLiked = true
          doAjax = false
        }
      })
      // Si post_id n'est pas encore présent, on l'ajoute
      if (alreadyLiked == false) {
        newCookieValue = currentCookieValue + ',' + post_id
        doAjax = true
        // Si post_id est déjà présent, on laisse le cookie en l'état
      } else {
        newCookieValue = currentCookieValue
      }
      // Si le cookie n'est pas défini, on le créé avec la valeur de post_id
      // On fait la requête Ajax
    } else {
      newCookieValue = post_id
      doAjax = true
    }
    // On fabrique un nouveau cookie avec des valeurs mises à jour
    document.cookie =
      'alreadyexist=' + newCookieValue + '; max-age=' + 30 * 6 * 24 * 60 * 60

    // ----------------------------------------------------------------------------
    // On construit la requête Ajax
    // ----------------------------------------------------------------------------

    if (doAjax == true) {
      $.ajax({
        url: AJAXCALLS.ajaxurl, // AJAXCALLS est une constante créée par la fonction add_ajax_scripts() en PHP (cf.functions.php)
        type: 'POST', // La méthode employée pour faire transiter l'information
        data: {
          action: 'update_post_likes', // Nom de la fonction à éxécuter côté serveur qui conditionne également le nom du hook WP à utiliser
          post_id: post_id,
          likes_count: likes_count,
        },
        dataType: 'json', // le type de données
        success: function (response) {
          // Pour débuggage
          //console.log(response)
        },
        error: function (error) {
          console.log(error)
        },
      }).done(function (response) {
        // response.target est le sélecteur qui a été cliqué
        // response.likes est le nombre de likes
        $(response.target).text(response.likes)
      })
    }
  })
})(jQuery)
