export default function useUtils() {

    function humanDate(timestamp) {
        const date = new Date(timestamp);
        return `${date.getDate()}/${date.getMonth() + 1}/${date.getFullYear()} ${date.getHours()}:${date.getMinutes()}:${date.getSeconds()} (UTC ${date.getTimezoneOffset() / 60})`;
    }

    function goto(route) {
        window.location.href = route;
    }

    function alert(msg, acceptBtn = "Aceptar", cancelBtn = "Cancelar") {
        const body = document.querySelector('body');
        const modal = `
        <div id="myModal" class="modal fade show" tabindex="-1" role="dialog" style="padding-right: 15px; display: block;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Confirmar</h5>
                        <button id="closeBtn" type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>${msg}</p>
                    </div>
                    <div class="modal-footer">
                        <button id="cancelBtn" type="button" class="btn" data-dismiss="modal">${cancelBtn}</button>
                        <button id="saveBtn" type="button" class="btn">${acceptBtn}</button>
                    </div>
                </div>
            </div>
        </div>`;

        body.innerHTML += modal;
        const theModal = document.getElementById('myModal');

        return new Promise((resolve,reject)=>{
            document.getElementById('cancelBtn').addEventListener('click',(e)=>{
                theModal.parentNode.removeChild(theModal);
                resolve(false);
            });
            document.getElementById('closeBtn').addEventListener('click',(e)=>{
                theModal.parentNode.removeChild(theModal);
                resolve(false);
            });

            document.getElementById('saveBtn').addEventListener('click',(e)=>{
                theModal.parentNode.removeChild(theModal);
                resolve(true);
            })
        })
    }

    return {
        humanDate,
        goto,
        alert
    }
}
