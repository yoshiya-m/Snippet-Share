require.config({ paths: { 'vs': 'https://cdnjs.cloudflare.com/ajax/libs/monaco-editor/0.20.0/min/vs' } });
require(['vs/editor/editor.main'], function () {
    var editor = monaco.editor.create(document.getElementById('editor-container'), {
        value: '',
        language: 'plaintext'
    });
    const languages = monaco.languages.getLanguages();

    let languageSelector = document.getElementById("languages");
    for (let i = 0; i < languages.length; i++) {
        option = `<option value="${languages[i].id}">${languages[i].id}</option>`;
        languageSelector.innerHTML += option;
    }

    languageSelector.addEventListener('change', function() {
        console.log("language changed: " + this.value);
        const selectedLanguage = this.value; // ユーザーが選択した言語
        monaco.editor.setModelLanguage(editor.getModel(), selectedLanguage);
    });
});
