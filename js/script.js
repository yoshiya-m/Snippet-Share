

// 共有URL作成ボタンにfetch

document.getElementById("share-btn").addEventListener("click", function() {
    console.log("btn pushed");
    // fetchでバックエンドに送る。テキストを送る
    fetch('localhost:8000/test')
    .then(response => response.json())
    .then(data => {
        console.log(data);
    })
})



