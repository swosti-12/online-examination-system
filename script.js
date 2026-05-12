// Login Form Redirection (No backend, just for demo)
document.getElementById("loginForm").addEventListener("submit", function(e) {
    e.preventDefault();
    window.location.href = "dashboard.html";
});

// Start Exam (Demo)
function startExam() {
    window.location.href = "exam.html";
}

// Timer Countdown (30 minutes)
let timeLeft = 30 * 60; // 30 minutes in seconds
const timer = document.getElementById("time");

function updateTimer() {
    const minutes = Math.floor(timeLeft / 60);
    const seconds = timeLeft % 60;
    timer.textContent = `${minutes}:${seconds < 10 ? '0' : ''}${seconds}`;
    timeLeft--;

    if (timeLeft < 0) {
        submitExam();
    }
}

setInterval(updateTimer, 1000);

// Submit Exam (Demo)
function submitExam() {
    alert("Exam submitted!");
    window.location.href = "result.html";
}

// Back to Dashboard
function goToDashboard() {
    window.location.href = "dashboard.html";
}