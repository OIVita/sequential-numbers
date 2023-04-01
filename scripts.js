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
        document.body.classList.add('loading');
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
    document.addEventListener("DOMContentLoaded", function() {
        document.body.classList.remove('loading');
    });
        const buttons = document.querySelectorAll('input[name^="button-"]');
        buttons.forEach(button => {
          button.addEventListener('mouseover', () => {
            const audio = new Audio(button.dataset.audio);
            audio.play();
          });
        });
        function submitForm() {
            document.getElementById("topTutto").submit();
        }
    const contatoreElemento = document.getElementById("timer");
    let startTime = parseFloat(localStorage.getItem("startTime")) || new Date().getTime() / 1000;
    if (contatoreElemento) {
      setInterval(() => {
        const elapsedTime = ((new Date().getTime() / 1000) - startTime).toFixed(2);
        const seconds = Math.floor(elapsedTime).toString();
        const decimal = (elapsedTime % 1).toFixed(2).substr(2);
        const contatoreStringa = `${seconds.padStart(2, "")}.${decimal}`;
        contatoreElemento.textContent = contatoreStringa;
        localStorage.setItem("startTime", startTime.toString());
      }, 10);
    }
    const resetButton = document.getElementById("reset");
    if (resetButton) {
      resetButton.addEventListener("click", () => {
        startTime = 0;
        localStorage.removeItem("startTime");
        contatoreElemento.textContent = "00.00";
      });
    }
    const startButton = document.getElementById("start");
    if (startButton) {
      startButton.addEventListener("click", () => {
        startTime = Date.now() / 1000;
        localStorage.setItem("startTime", startTime);
      });
    }
</script>