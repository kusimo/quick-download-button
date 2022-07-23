document.addEventListener('DOMContentLoaded', function () {

    //Bail execution if button is not on page 
    let elem_button_exist_on_page = document.querySelector('.g-btn.f-l');
    if (null === elem_button_exist_on_page || '' === elem_button_exist_on_page) return;

    document.addEventListener('click', function (event) {

        // If the clicked element doesn't have the right selector, bail
        if (!event.target.matches('.g-btn.f-l')) return;

        // Don't follow the link
        event.preventDefault();

        let button = event.target;
        let attachment_id = button.getAttribute('data-attachment-id');
        let download_url = button.getAttribute('data-page-id');
        let haveExternal = button.getAttribute('data-have-external');
        let pid = button.getAttribute('data-id');
        let waitTime = button.getAttribute('data-spinner');
        let Havemsg = button.getAttribute('data-have-external');
        let msg = button.getAttribute('data-msg');
        let seconds;


        // Create loading container
        let loadingContainer = document.createElement('div');
        loadingContainer.setAttribute('class', 'download-loading-container');
        // Create loader spinner
        let counterContainer = document.createElement('div');
        counterContainer.setAttribute('class', 'counterContainer');
        let loader = document.createElement('div');
        loader.setAttribute('class', 'qdbu-loader');
        counterContainer.append(loader)
        loadingContainer.append(counterContainer);

        // Create message
        let info = document.createElement('div');
        info.setAttribute('class', 'loading-msg');
        info.innerHTML = `<span class="msg">${msg}</span>`;
        if ("" !== msg && null !== msg && "false" !== Havemsg) {
            loadingContainer.append(info)
        }

        let container = button.parentNode;


        switch (waitTime) {
            case '10':
                seconds = 1000;
                break;
            case '20':
                seconds = 2000;
                break;
            case '30':
                seconds = 3000;
                break;
            default:
                seconds = 0;
                break;
        }


        attachment_id = parseInt(attachment_id) - parseInt(download_url);

        let download_external_url = button.getAttribute('data-external-url'); 

        function createLink(linkType, linkUrl) {
            let sec = parseInt(waitTime);
            if (seconds !== 0) {
                container.append(loadingContainer)
                let countdownMsg = document.createElement('span');
                countdownMsg.setAttribute('class', 'countdownMsg');
                document.querySelector('.counterContainer').append(countdownMsg);
                countdownMsg.innerHTML = sec;

                let countdown = setInterval(function () {

                    sec--;
                    countdownMsg.innerHTML = sec;
                    if (sec <= 0) {
                        clearInterval(countdown);
                        document.querySelector('.download-loading-container').remove();
                        extFileUrl(linkType, linkUrl)
                    }

                }, seconds);
            } else {

                extFileUrl(linkType, linkUrl)
            }
        }


        if (null !== download_external_url & "" !== download_external_url) {
            if ("false" !== haveExternal && "" !== download_external_url) {
                // From gutenberg
                createLink('external_link', download_external_url)
            } else {
                // From shortcode. @todo create another download method.
                ///*
                if (download_external_url.indexOf('?') === -1) {
                    download_external_url += '?download';
                }
                window.open(download_external_url, '_blank');
                //*/ 
            }

            return;

        } else {
            // This is internal Download
            createLink('aid', attachment_id)
        }

        /* This function check nonce and open the download url in a new tab. */
        function extFileUrl($type, $url) {
            var data = {
                'action': 'qdbu_download_ajax_referer',
                'security': quick_download_object.security
            }

            if (data.security) {

                let url = `${quick_download_object.redirecturl}?${$type}=${$url}&_wpnonce=${data.security}`;

                window.open(url, '_blank');

            }

        }

    }, false);

})




