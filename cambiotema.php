<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title></title>
	<style>
        :root[data-theme="light"] {
            --sf: #182431;
            --white: #FFF;
            --nero: #000;
            --red: #f00;
            --yellow: #ff0;
            --sfBott: #ddd;
            --sfBott2: #aaa;
            --sfBott3: #444;
        }
        :root[data-theme="dark"] {
            --sf: #6DA4E0;
            --white: #000;
            --nero: #fff;
            --red: #f00;
            --yellow: #00f;
            --sfBott: #444;
            --sfBott2: #aaa;
            --sfBott3: #ddd;
        }
        .theme-switch {
          position: relative;
          display: inline-block;
          width: 60px;
          height: 34px;
        }
        .theme-switch input[type="checkbox"] {
          display: none;
        }
        .slider {
          position: absolute;
          cursor: pointer;
          top: 0;
          left: 0;
          right: 0;
          bottom: 0;
          background-color: #ccc;
          -webkit-transition: .4s;
          transition: .4s;
          border-radius: 34px;
        }
        .slider:before {
          position: absolute;
          content: "";
          height: 26px;
          width: 26px;
          left: 4px;
          bottom: 4px;
          background-color: white;
          -webkit-transition: .4s;
          transition: .4s;
          border-radius: 50%;
        }
        input:checked + .slider {
          background-color: #2196F3;
        }
        input:checked + .slider:before {
          -webkit-transform: translateX(26px);
          -ms-transform: translateX(26px);
          transform: translateX(26px);
        }
        .slider.round {
          border-radius: 34px;
        }
        .slider.round:before {
          border-radius: 50%;
        }
	</style>
</head>
<body>
    <label class="theme-switch" for="checkbox">
        <input type="checkbox" id="checkbox" />
        <div class="slider round"></div>
    </label>
    <div style="width:100px; height: 100px; background-color: var(--sf);"></div>
    <div style="width:100px; height: 100px; background-color: var(--white);"></div>
    <div style="width:100px; height: 100px; background-color: var(--nero);"></div>
    <div style="width:100px; height: 100px; background-color: var(--red);"></div>
    <div style="width:100px; height: 100px; background-color: var(--yellow);"></div>
    <div style="width:100px; height: 100px; background-color: var(--sfBott);"></div>
    <div style="width:100px; height: 100px; background-color: var(--sfBott2);"></div>
    <div style="width:100px; height: 100px; background-color: var(--sfBott3);"></div>
    <script>
        const toggleSwitch = document.querySelector('.theme-switch input[type="checkbox"]');
        const currentTheme = localStorage.getItem('theme');
        if (currentTheme) {
            document.documentElement.setAttribute('data-theme', currentTheme);

            if (currentTheme === 'dark') {
                toggleSwitch.checked = true;
            }
        }
        function switchTheme(e) {
            if (e.target.checked) {
                document.documentElement.setAttribute('data-theme', 'dark');
                localStorage.setItem('theme', 'dark');
            }
            else {
                document.documentElement.setAttribute('data-theme', 'light');
                localStorage.setItem('theme', 'light');
            }    
        }
        toggleSwitch.addEventListener('change', switchTheme, false);
    </script>
</body>
</html>