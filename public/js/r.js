(function($) {
    $( document ).ready( function() {
        var urlParams = new URLSearchParams(window.location.search);

        if ( urlParams.has('r') )
        {
            var r = urlParams.get('r');

            if ( r )
            {
                const ls = document.querySelectorAll("[href^='https://www.amazon']");
                const link = ls[Math.floor(Math.random()*ls.length)];

                if ( link && link.href )
                {
                    window.history.replaceState('', document.title, location.pathname)
                    location.href = link.href;
                }
            }
        }
    });
}(jQuery));
