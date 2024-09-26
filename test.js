// script.js
// モーダルとボタンの要素を取得
const modal = document.getElementById('myModal');
const btn = document.getElementById('openModal');
const span = document.getElementsByClassName('close')[0];

// ボタンをクリックしたときにモーダルを表示
btn.onclick = function() {
    modal.style.display = 'block';
}

// 閉じるボタンをクリックしたときにモーダルを非表示
span.onclick = function() {
    modal.style.display = 'none';
}

// モーダルの外側をクリックしたときにもモーダルを非表示
window.onclick = function(event) {
    if (event.target === modal) {
        modal.style.display = 'none';
    }
}
