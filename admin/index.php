<html>

<head>
    <style>
        body {
            font-size: 1.5em;
        }

        * {
            font-family: 'Gill Sans', 'Gill Sans MT', 'Lato', Calibri, 'Trebuchet MS', sans-serif;
        }

        form {
            display: flex;
            flex-flow: column;
            max-width: 500px;
            margin: 125px auto 0 auto;
        }

        form > input {
            margin-bottom: 0.2em;
            font-size: 1em;
            padding: 0.25em;
        }

        form > #submit {
            background-color: black;
            color: white;
            border: 0;
        }

        #logo {
            height: 2.5em;
            max-width: 90vw;
            position: absolute;
            bottom: 0.5em;
            right: 0.5em
        }
    </style>
</head>

<body>
    <form action="home.php" method="post">
        <input type="text" name="email" placeholder="E-mailadresse" />
        <input type="password" name="password" placeholder="Kodeord" />
        <input id="submit" type="submit" value="Log ind" />
    </form>
    <img id="logo" src="sort_navnetræk_hedelands_veteranbane.svg" />
</body>

</html>