$(document).ready(function () {
	setDatetimePicker();
	$(".box .overlay").css('display', 'none')

	$("#btnReport").on('click', function () {
		$(".box .overlay").css('display', 'block')
		$.ajax({
			type: 'POST',
			url: '/ajax/report',
			dataType: 'json',
			data: {
				'reportType': $("#reportType").val(),
				'startDate': $("#startDate").val(),
				'endDate': $("#endDate").val(),
			},
			success: function (data, textStatus, xhr) {
				if (data === false) {
					$("#reportTable tr:has(td)").remove();
					$("#reportTable tr:has(th)").remove();
					$('#reportResult .emptyResult').css("display", "block")
					$('#reportResult .emptyResult').text("Khoảng thời gian báo cáo không hợp lệ !");
				} else if (typeof data == 'undefined' || data.length <= 1) {
					$("#reportTable tr:has(td)").remove();
					$("#reportTable tr:has(th)").remove();
					$('#reportResult .emptyResult').css("display", "block")

					$('#reportResult .emptyResult').text("Không có doanh thu trong khoảng thời gian này !");
				} else {
					$('#reportResult .emptyResult').css("display", "none")
					switch (data[0].reportType) {
						case 'month': {
							$("#reportTable tr:has(td)").remove();
							$("#reportTable tr:has(th)").remove();
							$('#reportTable').append('<tr><th>Năm</th><th>Tháng</th><th>Doanh thu</th></tr>')
							data.shift()
							data.forEach(years => {
								var info_data = '<tr><td rowspan =' + years.length + '>' + years[0].year + '</td><td>' + years[0].month + '</td><td>' + formatMoney(years[0].income) + '</td>';
								$('#reportTable').append(info_data);

								years.forEach(year => {
									if (year.month != years[0].month) {
										var income = formatMoney(year.income);
										info_data = "";
										info_data += '<tr>';
										info_data += '<td >' + year.month + '</td>';
										info_data += '<td >' + income + '</td>';
										info_data += '</tr>';
										$('#reportTable').append(info_data);
									}
								})
							})
							break;
						}
						case 'quarter': {
							$("#reportTable tr:has(td)").remove();
							$("#reportTable tr:has(th)").remove();
							$('#reportTable').append('<tr><th>Năm</th><th>Quý</th><th>Doanh thu</th></tr>')
							data.shift()
							data.forEach(years => {
								var info_data = '<tr><td rowspan =' + years.length + '>' + years[0].year + '</td><td>' + years[0].quarter + '</td><td>' + formatMoney(years[0].income) + '</td>';
								$('#reportTable').append(info_data);

								years.forEach(year => {
									if (year.quarter != years[0].quarter) {
										var income = formatMoney(year.income);
										info_data = "";
										info_data += '<tr>';
										info_data += '<td >' + year.quarter + '</td>';
										info_data += '<td >' + income + '</td>';
										info_data += '</tr>';
										$('#reportTable').append(info_data);
									}
								})
							})
							break;
						}
						case 'year': {
							$("#reportTable tr:has(td)").remove();
							$("#reportTable tr:has(th)").remove();
							$('#reportTable').append('<tr><th>Năm</th><th>Doanh thu</th></tr>')
							data.shift()
							data.forEach(year => {

								var income = formatMoney(year.income);
								var info_data = "";
								info_data += '<tr>';
								info_data += '<td >' + year.year + '</td>';
								info_data += '<td >' + income + '</td>';
								info_data += '</tr>';
								$('#reportTable').append(info_data);

							})
							break;
						}

					}

					$("#reportTable").css('display', 'table')

				}
				$(".box .overlay").css('display', 'none')

			},
			error: function (xhr, textStatus, errorThrown) {
				console.log(errorThrown);
			}
		})
	})
})

function setDatetimePicker() {
	$("#startDate").datetimepicker({
		timepicker: false,
		format: 'Y-m-d'
	});
	$("#endDate").datetimepicker({
		timepicker: false,
		format: 'Y-m-d'
	});
}

function formatMoney(amount, decimalCount = 2, thousands = ",") {
    try {
        decimalCount = Math.abs(decimalCount);
        decimalCount = isNaN(decimalCount) ? 2 : decimalCount;

        const negativeSign = amount < 0 ? "-" : "";

        let i = parseInt(amount = Math.abs(Number(amount) || 0).toFixed(decimalCount)).toString();
        let j = (i.length > 3) ? i.length % 3 : 0;

        return negativeSign + (j ? i.substr(0, j) + thousands : '') + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + thousands) + " VNĐ";
    } catch (e) {
        console.log(e)
    }
}