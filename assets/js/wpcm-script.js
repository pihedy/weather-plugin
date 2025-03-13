/* WPCM Scripts */

jQuery(($) => {

    $(document).ready(() => {
        let container = $('#weather-container');

        if (container.length <= 0) {
            return;
        }

        $(container).text('Loading...');

        $.ajax({
            url: `/wp-json/weather/v1/city/${$(container).data('location')}`,
            method: "GET",
            dataType: "json",
            success: function(response) {
                if (response.status.code !== 200) {
                    $(container).text(`Error: ${response.status.message}`);

                    return;
                }

                if (!response.data.main || typeof response.data.main.temp === "undefined") {
                    $(container).text("An error occurred: no valid data was received.");

                    return;
                }

                const temperature = Math.round(response.data.main.feels_like);

                $(container).text(`${response.data.name}: ${temperature}°C`);
            },
            error: function(valami) {
                $(container).text("An error occurred during the query.");
            }
        });
    });

});
