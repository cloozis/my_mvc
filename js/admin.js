(function(){
    "use strict";
    const openEditorLink = document.querySelector("#openEditor"),
    editorBlock = document.querySelector("#mainContent");

    const getData = (editor) => {


        let block = document.querySelector( '.lh-1' );
        block.insertAdjacentHTML("beforeend", '<button id="submit" class="btn btn-outline-success saveButton">Сохранить</button>');

        document.querySelector( '#submit' ).addEventListener( 'click', () => {

            let editorData = editor.getData();

            const url = '/index.php';

            const currentURL = window.location.href;

            const data = { save:'save_page', content: editorData, url: currentURL };
            // Options for the fetch request
            const options = {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
            };

            // Make the POST request
            fetch(url, options)
            .then(response => response.json())
            .then(data => {
                console.log(data)
                window.location.reload(true)
            })
            .catch(error => console.error('Error:', error));

        });
    }

    const eOpen = (e) => {
        e.target.classList.add('hide');
        let editor = ClassicEditor
        .create( editorBlock, {

            // toolbar:
            // {
                //name: 'source',
                //items: ['codeBlock','|','undo', 'redo', '|', 'heading', '|', 'bold', 'italic', '|', 'link', 'uploadImage', 'insertTable', 'blockQuote', 'mediaEmbed', '|', 'bulletedList', 'numberedList', 'outdent', 'indent'],
            // },
            ckfinder:
            {
                uploadUrl: '/index.php'
            },
        })
        .then(editor => {
            getData(editor);
        })
        .catch( error => {
            console.error( error );
        } );
        console.log(ClassicEditor.prototype);
    }

    openEditorLink.addEventListener("click", eOpen);

})();
