window.getServerStatus = function(callback = function(response) {}) {
    const xhr = new XMLHttpRequest();
  
    xhr.open('GET', window.location.origin + '/api/serverlist/fetch', true);

    xhr.onload = function (event) {
        if ((event.target.status == 200) && (event.target.readyState == 4)) {
            const data = JSON.parse(event.target.responseText);
            callback(data);
        } else {
            console.error('Request error', event.target.status, event.target.statusText);
        }
    };

    xhr.onerror = function (event) {
        console.error(event);
    };

    xhr.send();
};

window.fetchServerStatuses = function(target) {
    target.innerHTML = '';

    window.getServerStatus(function(response) {
        for (let i = 0; i < response.data.length; i++) {
            target.innerHTML += `
                <a href="` + response.data[i].host + `">
                    <div class="server-item" style="background-image: url('` + response.data[i].sv_preview + `');">
                        <div class="server-item-count">` + (response.data[i].users.match(/[^,]+/g)?.length ?? 0) + ` chatters</div>

                        <div class="server-item-info">
                            <div class="server-item-info-name">` + response.data[i].sv_name + `</div>
                            <div class="server-item-info-topic">` + response.data[i].sv_topic + `</div>
                        </div>
                    </div>
                </a>
            `;
        }
    });
};

document.addEventListener('DOMContentLoaded', function() {
});