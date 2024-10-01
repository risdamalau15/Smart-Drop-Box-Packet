const hamBurger = document.querySelector(".toggle-btn");

hamBurger.addEventListener("click", function () {
  document.querySelector("#sidebar").classList.toggle("expand");
});

// Script AJAX
document.getElementById('searchForm').addEventListener('submit', function(e) {
    e.preventDefault();
    var searchResi = document.getElementsByName('search')[0].value;

    var xhr = new XMLHttpRequest();
    xhr.open('POST', '../includes/get_data_search.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            var resultTable = document.getElementById('resultTable');
            if (resultTable) {
                resultTable.innerHTML = xhr.responseText;
            } else {
                console.error('Element with ID "resultTable" not found.');
            }
        }
    };
    xhr.send('search=' + searchResi);
});


