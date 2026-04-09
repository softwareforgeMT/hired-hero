    var choicesExamples = document.querySelectorAll("[data-choices]");
    if(choicesExamples){
        Array.from(choicesExamples).forEach(function (item) {
        var choiceData = {};
        var isChoicesVal = item.attributes;
        if (isChoicesVal["data-choices-groups"]) {
            choiceData.placeholderValue = "This is a placeholder set in the config";
        }
        if (isChoicesVal["data-choices-search-false"]) {
            choiceData.searchEnabled = false;
        }
        if (isChoicesVal["data-choices-search-true"]) {
            choiceData.searchEnabled = true;
        }
        if (isChoicesVal["data-choices-removeItem"]) {
            choiceData.removeItemButton = true;
        }
        // if (isChoicesVal["data-choices-sorting-false"]) {
        //     choiceData.shouldSort = false;
        // }
        if (isChoicesVal["data-choices-sorting-true"]) {
            choiceData.shouldSort = true;
        }else{
            choiceData.shouldSort = false;
        }
        if (isChoicesVal["data-choices-multiple-remove"]) {
            choiceData.removeItemButton = true;
        }
        if (isChoicesVal["data-choices-limit"]) {
            choiceData.maxItemCount = isChoicesVal["data-choices-limit"].value.toString();
        }
        if (isChoicesVal["data-choices-limit"]) {
            choiceData.maxItemCount = isChoicesVal["data-choices-limit"].value.toString();
        }
        if (isChoicesVal["data-choices-editItem-true"]) {
            choiceData.maxItemCount = true;
        }
        if (isChoicesVal["data-choices-editItem-false"]) {
            choiceData.maxItemCount = false;
        }
        if (isChoicesVal["data-choices-text-unique-true"]) {
            choiceData.duplicateItemsAllowed = false;
        }
        if (isChoicesVal["data-choices-text-disabled-true"]) {
            choiceData.addItems = false;
        }
        isChoicesVal["data-choices-text-disabled-true"] ? new Choices(item, choiceData).disable() : new Choices(item, choiceData);
    }); 
    }


    var flatpickrExamples = document.querySelectorAll("[data-provider]");
    if(flatpickrExamples){
        Array.from(flatpickrExamples).forEach(function (item) {
            if (item.getAttribute("data-provider") == "flatpickr") {
                var dateData = {};
                var isFlatpickerVal = item.attributes;
                if (isFlatpickerVal["data-date-format"])
                    dateData.dateFormat = isFlatpickerVal["data-date-format"].value.toString();
                if (isFlatpickerVal["data-enable-time"]) {
                    (dateData.enableTime = true),
                        (dateData.dateFormat = isFlatpickerVal["data-date-format"].value.toString() + " H:i");
                }
                if (isFlatpickerVal["data-altFormat"]) {
                    (dateData.altInput = true),
                        (dateData.altFormat = isFlatpickerVal["data-altFormat"].value.toString());
                }
                if (isFlatpickerVal["data-minDate"]) {
                    dateData.minDate = isFlatpickerVal["data-minDate"].value.toString();
                    dateData.dateFormat = isFlatpickerVal["data-date-format"].value.toString();
                }
                if (isFlatpickerVal["data-maxDate"]) {
                    dateData.maxDate = isFlatpickerVal["data-maxDate"].value.toString();
                    dateData.dateFormat = isFlatpickerVal["data-date-format"].value.toString();
                }
                if (isFlatpickerVal["data-deafult-date"]) {
                    dateData.defaultDate = isFlatpickerVal["data-deafult-date"].value.toString();
                    dateData.dateFormat = isFlatpickerVal["data-date-format"].value.toString();
                }
                if (isFlatpickerVal["data-multiple-date"]) {
                    dateData.mode = "multiple";
                    dateData.dateFormat = isFlatpickerVal["data-date-format"].value.toString();
                }
                if (isFlatpickerVal["data-range-date"]) {
                    dateData.mode = "range";
                    dateData.dateFormat = isFlatpickerVal["data-date-format"].value.toString();
                }
                if (isFlatpickerVal["data-inline-date"]) {
                    (dateData.inline = true),
                        (dateData.defaultDate = isFlatpickerVal["data-deafult-date"].value.toString());
                    dateData.dateFormat = isFlatpickerVal["data-date-format"].value.toString();
                }
                if (isFlatpickerVal["data-disable-date"]) {
                    var dates = [];
                    dates.push(isFlatpickerVal["data-disable-date"].value);
                    dateData.disable = dates.toString().split(",");
                }
                if (isFlatpickerVal["data-week-number"]) {
                    var dates = [];
                    dates.push(isFlatpickerVal["data-week-number"].value);
                    dateData.weekNumbers = true
                }
                flatpickr(item, dateData);
            } else if (item.getAttribute("data-provider") == "timepickr") {
                var timeData = {};
                var isTimepickerVal = item.attributes;
                if (isTimepickerVal["data-time-basic"]) {
                    (timeData.enableTime = true),
                        (timeData.noCalendar = true),
                        (timeData.dateFormat = "H:i");
                }
                if (isTimepickerVal["data-time-hrs"]) {
                    (timeData.enableTime = true),
                        (timeData.noCalendar = true),
                        (timeData.dateFormat = "H:i"),
                        (timeData.time_24hr = true);
                }
                if (isTimepickerVal["data-min-time"]) {
                    (timeData.enableTime = true),
                        (timeData.noCalendar = true),
                        (timeData.dateFormat = "H:i"),
                        (timeData.minTime = isTimepickerVal["data-min-time"].value.toString());
                }
                if (isTimepickerVal["data-max-time"]) {
                    (timeData.enableTime = true),
                        (timeData.noCalendar = true),
                        (timeData.dateFormat = "H:i"),
                        (timeData.minTime = isTimepickerVal["data-max-time"].value.toString());
                }
                if (isTimepickerVal["data-default-time"]) {
                    (timeData.enableTime = true),
                        (timeData.noCalendar = true),
                        (timeData.dateFormat = "H:i"),
                        (timeData.defaultDate = isTimepickerVal["data-default-time"].value.toString());
                }
                if (isTimepickerVal["data-time-inline"]) {
                    (timeData.enableTime = true),
                        (timeData.noCalendar = true),
                        (timeData.defaultDate = isTimepickerVal["data-time-inline"].value.toString());
                    timeData.inline = true;
                }
                flatpickr(item, timeData);
            }
        });
    }
       


    var isApexSeriesData = {};
    var isApexSeries = document.querySelectorAll("[data-chart-series]");
    if (isApexSeries) {
      Array.from(isApexSeries).forEach(function (element) {
        var isApexSeriesVal = element.attributes;

        if (isApexSeriesVal["data-chart-series"]) {
          isApexSeriesData.series = isApexSeriesVal["data-chart-series"].value.toString();
          var radialbarhartoneColors = getChartColorsArray(isApexSeriesVal["id"].value.toString());
          var options = {
            series: [isApexSeriesData.series],
            chart: {
              type: 'radialBar',
              width: 70,
              height: 70,
              sparkline: {
                enabled: true
              }
            },
            dataLabels: {
              enabled: false
            },
            plotOptions: {
              radialBar: {
                hollow: {
                  margin: 0,
                  size: '60%'
                },
                track: {
                  margin: 1
                },
                dataLabels: {
                  showOn: "always",
                  name: {
                    show: false,               
                  },
                  value: {
                    color: radialbarhartoneColors[0],
                    fontSize: '14px',
                    offsetY: 4,
                    show: true,
                  },
                  total: {
                    show: false,
                    
                  }
                }
              }
            },
            colors: radialbarhartoneColors
          };
          var chart = new ApexCharts(document.querySelector("#" + isApexSeriesVal["id"].value.toString()), options);
          chart.render();
        }
      });
    }
    // get colors array from the string
    function getChartColorsArray(chartId) {
      if (document.getElementById(chartId) !== null) {
        var colors = document.getElementById(chartId).getAttribute("data-colors");

        if (colors) {
          colors = JSON.parse(colors);
          return colors.map(function (value) {
            var newValue = value.replace(" ", "");

            if (newValue.indexOf(",") === -1) {
              var color = getComputedStyle(document.documentElement).getPropertyValue(newValue);
              if (color) return color;else return newValue;
              ;
            } else {
              var val = value.split(',');

              if (val.length == 2) {
                var rgbaColor = getComputedStyle(document.documentElement).getPropertyValue(val[0]);
                rgbaColor = "rgba(" + rgbaColor + "," + val[1] + ")";
                return rgbaColor;
              } else {
                return newValue;
              }
            }
          });
        } else {
          console.warn('data-colors Attribute not found on:', chartId);
        }
      }
    } // Projects Overview
