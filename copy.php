 events: [
    // Pause déjeuner
    {
        daysOfWeek: [2,3,4,5,6], // Mardi à Samedi
        startTime: '12:00',
        endTime: '14:00',
        display: 'background',
        color: '#ff0000',
       
    },
    // Week-end en gris foncé
    {
        daysOfWeek: [0,1], 
        startTime: '08:00',
        endTime: '19:00',
        display: 'background',
        color: '#444444', // gris foncé
        
    },
    {{ events|raw }}
],
    dateClick: function(info) {
        window.location.href = '/rendez/vous/new?date=' + encodeURIComponent(info.dateStr);
    }
});
            calendar.render();
        });
    </script>
{% endblock %}