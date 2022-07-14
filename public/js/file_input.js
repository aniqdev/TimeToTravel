function htmlToElement(html) {
    var template = document.createElement('template');
    html = html.trim(); // Never return a text node of whitespace as the result
    template.innerHTML = html;
    return template.content.firstChild;
}

function AddFileInput() {
    var inputBlock = document.getElementById("imageInputBlock");

    var newdiv = htmlToElement(
        `                   
        <div class="input-group my-2">
                    <input type="file" class="form-control" name="images[]" accept="image/*">
                    <button class="btn btn-danger" type="button" onclick="return this.parentNode.remove();">Удалить</button>
        </div>
        `);
    inputBlock.appendChild(newdiv);
}