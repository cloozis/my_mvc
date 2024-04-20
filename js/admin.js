(function(){
    "use strict";
    const openEditorLink = document.querySelector("#openEditor"),
    editorBlock = document.querySelector("#mainContent");

    const editFields = {
        'title':'',
        'meta_d':'',
        'meta_k':'',
    }

    const addEditFields = (e) => {
        let fields = Object.keys(editFields);
        let dataParams = editorBlock.dataset;
        fields.map(elem => {
            e.insertAdjacentHTML("afterBegin", `
            <input name="${elem}" class="editFields" value="${dataParams[elem]}" placeholder="${elem}">
            `);
        })
    }

    let getFieldsData = () => {
        let fields = document.querySelectorAll('.editFields');
        fields.forEach(elem => {
            editFields[elem.name] = elem.value;
        })

        return editFields;
    }

    const getData = (editor) => {


        let block = document.querySelector( '.lh-1' );

        block.insertAdjacentHTML("beforeend", '<button id="submit" class="btn btn-outline-success saveButton">Сохранить</button>');

        addEditFields(block);

        document.querySelector( '#submit' ).addEventListener( 'click', () => {

            let editorData = editor.getData();

            const url = '/index.php';

            const currentURL = window.location.href;

            let dataFields = getFieldsData();

            const data = { save:'save_page', content: editorData, ...dataFields, url: currentURL };
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
                // console.log(data)
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
        });
        // console.log(ClassicEditor.prototype);
    }

    openEditorLink.addEventListener("click", eOpen);

})();
