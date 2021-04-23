function convertDateTimeToString(date) {
    let dateConverted = '';
    let dateData = new Date(date);
    let month = dateData.getMonth();
    let hours = dateData.getHours();
    let minutes = dateData.getMinutes();

    if (parseInt(month) < 9)
        dateConverted = dateData.getFullYear() + '-' + '0' + (parseInt(month + 1)).toString() + '-' + dateData.getDate();
    else
        dateConverted = dateData.getFullYear() + '-' + (parseInt(month + 1)).toString() + '-' + dateData.getDate();

    if (parseInt(hours) < 9)
        dateConverted += ' 0' + hours;
    else
        dateConverted += ' ' + hours;

    if (parseInt(minutes) < 9)
        dateConverted += ':0' + minutes;
    else
        dateConverted += ':' + minutes;

    return dateConverted;
}