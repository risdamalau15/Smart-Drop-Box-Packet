document.addEventListener('DOMContentLoaded', function() {

    const data = {
        dailyVisits: 8457,
        sales: 52160,
        comments: 15823,
        numberOfVisits: 36752
    };

    document.querySelector('.widget .info h3').innerText = data.dailyVisits;
});