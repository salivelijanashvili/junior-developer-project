let match = false;
let userData = [];
fetch('server/data.php')
  .then(res => res.json())
  .then(data => userData.push(data));

const nameInput = document.getElementById('name');
const newUsernameInput = document.getElementById('newUser');

// check if username exist in login form
if (nameInput) {
  nameInput.addEventListener('input', e => {
    userData[0].includes(e.target.value) ? (match = true) : (match = false);
    match ? setColor(nameInput, 'green', 500) : setColor(nameInput, 'red', 500);
  });
}

// check if username exist in register form
if (newUsernameInput) {
  newUsernameInput.addEventListener('input', e => {
    userData[0].includes(e.target.value) ? (match = true) : (match = false);
    match
      ? setColor(newUsernameInput, 'red', 600)
      : setColor(newUsernameInput, '#495057', 600);
  });
}
// input style
function setColor(input, color, time) {
  setTimeout(() => (input.style.color = color), time);
}

// responsive navbar
function myFunction() {
  const nav = document.querySelector('.topnav');
  if (nav.className === 'navbar sticky-top topnav') {
    nav.className += ' responsive';
  } else {
    nav.className = 'navbar sticky-top topnav';
  }
}
// jquery
$(document).ready(function () {
  $('[data-toggle="tooltip"]').tooltip();
});
