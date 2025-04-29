/**
 * @Project NUKEVIET MUSIC 4.X
 * @Author PHAN TAN DUNG (writeblabla@gmail.com)
 * @Copyright (C) 2016 PHAN TAN DUNG. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Sun, 26 Feb 2017 14:04:32 GMT
 */

// Closure to prevent this code from conflicting with other scripts
(function(jwplayer) {

    // The initialization function, called on player setup.
    var template = function(player, config, div) {

        // When the player is ready, let's add some text.
        player.onReady(setup);

        function setup(evt) {
            div.style.color = "red";
            if (config.text) {
                div.innerHTML = config.text;
            } else {
                div.innerHTML = "Hello World!";
            }

            console.log('ACSASC');
        };

        // This function is required. Let's use it to center the text.
        this.resize = function(width, height) {
            div.style.position = 'absolute';
            div.style.width = '100px';
            div.style.height = '20px';
            div.style.left = (width / 2 - 50) + 'px';
            div.style.top = (width / 2 - 10) + 'px';
        };

    };

    // This line registers above code as a 6.0 compatible plugin called "helloworld".
    jwplayer().registerPlugin('helloworld', '6.0', template);

})(jwplayer);
