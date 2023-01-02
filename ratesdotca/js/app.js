/**
 * Javascript for login, and burger menu behaviors/CSS
 *
 * @author Josephson Eduardo <josephson.eduardo@gmail.com>
 */
document.addEventListener("DOMContentLoaded", function() {

  // Login onclick event function
  document.getElementById('login-link').addEventListener('click', function(e) {
    e.preventDefault();
    // unhide burger menu links that should show only when "Login" is clicked
    var i, showOnLoginElements = document.getElementsByClassName('show-on-login');
    for (i = 0; i < showOnLoginElements.length; i++) {
      showOnLoginElements[i].classList.remove('hidden');
    }
    // unhide burger menu
    document.getElementById('burger-menu').classList.remove('hidden');
    setTimeout(function() {
      // Scroll to bottom of page
      window.scrollTo(0, document.body.scrollHeight);
    }, 300);
  });

  // Close menu onclick event function
  document.getElementById('close-menu').addEventListener('click', function() {
    // Make the JS to reset to the original list
    var i, showOnLoginElements = document.getElementsByClassName('show-on-login');

    // Hide immediately if there's no "Login" links
    if (document.querySelectorAll('.show-on-login.hidden').length > 0) {
      document.getElementById('burger-menu').classList.add('hidden');
    } else {
      for (i = 0; i < showOnLoginElements.length; i++) {
        showOnLoginElements[i].classList.add('hidden');
      }

      // hide burger menu
      // jump back to the top of the page
      setTimeout(function() {
        document.getElementById('burger-menu').classList.add('hidden');
        window.scrollTo(0, 0);
      }, 200);
    }

  });

  // CSS-related functions for hover
  document.getElementById('hamburger-menu').addEventListener('mouseover', function() {
    document.getElementById('burger-menu-trigger').className = 'burger-menu-trigger hover';
  });

  document.getElementById('hamburger-menu').addEventListener('mouseout', function() {
    document.getElementById('burger-menu-trigger').className = 'burger-menu-trigger';
  });

  document.getElementById('hamburger-menu').addEventListener('click', function() {
    document.getElementById('burger-menu').classList.remove('hidden');
  });

  // Pagination page links (CSS only)
  var pagerPageLinks = document.querySelectorAll('.pager-page .page-link');
  for (var i = 0; i < pagerPageLinks.length; i++) {
    pagerPageLinks[i].addEventListener('click', function(e) {
      document.querySelector('.pager-page.current').classList.remove('current');
      e.target.parentNode.parentNode.classList.add('current');
      e.preventDefault();
    });
  }

});