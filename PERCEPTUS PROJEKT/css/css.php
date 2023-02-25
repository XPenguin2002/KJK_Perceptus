<?php header("Content-type: text/css"); ?>
body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f1f1f1;
}

.files-container {
    width: 80%;
    height: 80%;
    margin: 0 auto;
    text-align: center;
    border: 1px solid #ccc;
    border-radius: 4px;
    background-color: white;
    position: relative;
}

h1 {
    margin-top: 0;
    padding: 20px;
    background-color: grey;
    border-bottom: 1px solid #ccc;
}

h2 {
    padding: 20px;
}

.file-item {
    display: flex;
    align-items: center;
    padding: 20px;
    border-bottom: 1px solid #ccc;
}

.file-item:last-child {
    border-bottom: none;
}

.file-icon {
    flex: 0 0 50px;
    height: 50px;
    margin-right: 20px;
}

.file-name {
    flex: 1;
    font-size: 18px;
}

.file-actions {
    flex: 0 0 50px;
    display: flex;
    justify-content: space-around;
}

.file-actions a {
    color: #333;
    text-decoration: none;
}

.file-actions a:hover {
    color: #4caf50;
}

.error {
    color: red;
    margin-top: 10px;
}
.wyloguj {
    width: 200px;
    padding: 12px 20px;
    margin-bottom: 20px;
    box-sizing: border-box;
    border: none;
    border-radius: 4px;
    background-color: #4caf50;
    color: #fff;
    cursor: pointer;
    background-color: #45a049;
    position:absolute;
    bottom:0;
    left:20px;
    text-align: center;
}
.element1 {
    display: inline-block;
    width: 25%;
    height: 100%;
    position:absolute;
    top:0;
    left:0;
}
.element2 {
    display: inline-block;
    width: 75%;
    height: 100%;
    position:absolute;
    top:0;
    right:0;
}
.przyciski{
    width: 80%;
    margin: 0 auto;
    text-align: center;
}
.ulubione, .moje, .wszystkie, .kosz {
    width: 200px;
    padding: 12px 20px;
    box-sizing: border-box;
    border: none;
    border-radius: 4px;
    background-color: #4caf50;
    color: #fff;
    cursor: pointer;
    background-color: #45a049;
    text-align: center;
}
.dol{
    position: absolute;
    bottom: 0;
    left: 0;
    border-top: 1px solid #ccc;
    width: 100%;
}
.flex{
    display: flex;
}
.zajete{
    padding: 20px;
}
.ustawienia {
    width: 200px;
    padding: 12px 20px;
    box-sizing: border-box;
    border: none;
    border-radius: 4px;
    color: #fff;
    background-color: #45a049;
    position: relative;
    left:20px;
    text-align: center;
}
.ustawienia2 {
    width: 200px;
    padding: 12px 20px;
    box-sizing: border-box;
    border: none;
    border-radius: 4px;
    color: #fff;
    cursor: pointer;
    background-color: #88C28A;
    position: relative;
    left:20px;
    text-align: center;
}
ol {
    width: 200px;
    list-style-type:none;
    padding:0;
    margin:0;
}
ul {
    width: 200px;
    list-style-type:none;
    padding:0;
    margin:0;
}
ol > li > ul {
    display:none;
}

ol > li:hover > ul {
    display:block;
}
#po1{
    display:none;
    position: absolute;
    top:0;
    bottom:0;
    left:0;
    right:0;
}
#po2{
    display:none;
    position: absolute;
    top:0;
    bottom:0;
    left:0;
    right:0;
}
#po3{
    display:none;
    position: absolute;
    top:0;
    bottom:0;
    left:0;
    right:0;
}
#po4{
    display:none;
    position: absolute;
    top:0;
    bottom:0;
    left:0;
    right:0;
    z-index: 1;
}
#pop1{
    display:block;
    position:absolute;
    width:50%;
    height:50%;
    background:#f1f1f1;
    top:50%;
    left:50%;
    transform:translate(-50%,-50%);
    text-align:center;
    padding:0 30px 30px;
    border-radius: 6px;
    border-style: solid;
}
#pop1 button{
    position:absolute;
    top:0;
    right:0;
    width:30px;
    margin-top: 25px;
    margin-right: 25px;
    padding:10px 0;
    background:#6fd649;
    color:#fff;
    border:0;
    outline:none;
    font-size:18px;
    border-radius:4px;
    cursor:pointer;
    box-shadow:0 5px 5px rgba(0,0,0,0.2);
}
#pop2{
    display:block;
    position:absolute;
    width:50%;
    height:50%;
    background:#f1f1f1;
    top:50%;
    left:50%;
    transform:translate(-50%,-50%);
    text-align:center;
    padding:0 30px 30px;
    border-radius: 6px;
    border-style: solid;
}
#pop2 button{
    position:absolute;
    top:0;
    right:0;
    width:30px;
    margin-top: 25px;
    margin-right: 25px;
    padding:10px 0;
    background:#6fd649;
    color:#fff;
    border:0;
    outline:none;
    font-size:18px;
    border-radius:4px;
    cursor:pointer;
    box-shadow:0 5px 5px rgba(0,0,0,0.2);
}
#pop3{
    display:block;
    position:absolute;
    width:50%;
    height:50%;
    background:#f1f1f1;
    top:50%;
    left:50%;
    transform:translate(-50%,-50%);
    text-align:center;
    padding:0 30px 30px;
    border-radius: 6px;
    border-style: solid;
}
#pop3 button{
    position:absolute;
    top:0;
    right:0;
    width:30px;
    margin-top: 25px;
    margin-right: 25px;
    padding:10px 0;
    background:#6fd649;
    color:#fff;
    border:0;
    outline:none;
    font-size:18px;
    border-radius:4px;
    cursor:pointer;
    box-shadow:0 5px 5px rgba(0,0,0,0.2);
}
#pop4{
    display:block;
    position:absolute;
    width:50%;
    height:50%;
    background:#f1f1f1;
    top:50%;
    left:50%;
    transform:translate(-50%,-50%);
    text-align:center;
    padding:0 30px 30px;
    border-radius: 6px;
    border-style: solid;
}
#pop4 button{
    position:absolute;
    top:0;
    right:0;
    width:30px;
    margin-top: 25px;
    margin-right: 25px;
    padding:10px 0;
    background:#6fd649;
    color:#fff;
    border:0;
    outline:none;
    font-size:18px;
    border-radius:4px;
    cursor:pointer;
    box-shadow:0 5px 5px rgba(0,0,0,0.2);
}
.login-container {
        width: 400px;
        margin:  5% auto;
        background-color: #fff;
        border-radius: 5px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.3);
        padding: 20px;
    }

    .login-container h1 {
        text-align: center;
        margin-bottom: 20px;
    }

    .login-container label {
        display: block;
        margin-bottom: 10px;
    }

    .login-container input[type="text"],
    .login-container input[type="password"] {
        width: 100%;
        padding: 12px 20px;
        margin-bottom: 20px;
        box-sizing: border-box;
        border: 1px solid #ccc;
        border-radius: 4px;
        outline: none;
    }

    .login-container input[type="submit"] {
        width: 100%;
        padding: 12px 20px;
        margin-bottom: 20px;
        box-sizing: border-box;
        border: none;
        border-radius: 4px;
        background-color: #4caf50;
        color: #fff;
        cursor: pointer;
    }

    .login-container input[type="submit"]:hover {
        background-color: #45a049;
    }