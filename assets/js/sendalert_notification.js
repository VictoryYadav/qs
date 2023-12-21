function get_Notification() {
    return;
    $.ajax({
        url: 'ajax/get_notification_ajax.php',
        type: 'get',
        data: {
            'status': 1
        },
        success: function(response) {
            if (response != 0) {
                var array = JSON.parse(response);
                alert(array['title'] + '\n' + array['message']);
                updateNotification(array['id']);
            }
        }
    })
    setTimeout(function() { get_Notification(); }, 2000);
}

get_Notification();

function updateNotification(id) {

    $.ajax({
        url: 'ajax/get_notification_ajax.php',
        type: 'get',
        data: {
            'status': 2,
            'id': id
        }
    })
    setTimeout(function() { get_Notification(); }, 2000);
}

// https://thedebuggers.com/english-number-translation-php-js/
function convertToUnicodeNo(amount){
    var site_lang = document.getElementById('site_lang').value;

    var englishDigits = {
        '०': '0',
        '१': '1',
        '२': '2',
        '३': '3',
        '४': '4',
        '५': '5',
        '६': '6',
        '७': '7',
        '८': '8',
        '९': '9'
      };

      var devanagariDigits = {
        '0': '०',
        '1': '१',
        '2': '२',
        '3': '३',
        '4': '४',
        '5': '५',
        '6': '६',
        '7': '७',
        '8': '८',
        '9': '९'
      };
  
    let digits = 0 ;
    // 1=english, 2=hindi, 3=malay, 4=thai
    if(amount){
        switch (site_lang) {
          case '1':
            // digits = amount.replace(/[०१२३४५६७८९]/g, function(s) {
            //           return englishDigits[s];
            //       });
            digits = amount;
            break;
          case '2':
            digits = amount.toString().replace(/[0123456789]/g, function(s) {
                        return devanagariDigits[s];
                    });
            break;
        }
    }
    return digits;

    console.log('hi lang '+digits + ' '+site_lang);
}

function convertDigitToEnglish(amount){

    var englishDigits = {
        '०': '0',
        '१': '1',
        '२': '2',
        '३': '3',
        '४': '4',
        '५': '5',
        '६': '6',
        '७': '7',
        '८': '8',
        '९': '9'
      };

    let digits = 0 ;
    if(amount){
        var languageDigit = checkDigit(amount);    
        switch (languageDigit) {
            case 'EnglishDigit':
                digits = amount;
            break;

          case 'HindiDigit':
                digits = amount.replace(/[०१२३४५६७८९]/g, function(s) {
                      return englishDigits[s];
                  });
            break;
            
        }
    }
    return digits;

}

function checkDigit(character) {
  const code = character.charCodeAt(0);
  // Unicode ranges for English digits (0-9) and Hindi digits (०-९)
  const englishDigitRange = [48, 57]; // Decimal code point range for English digits
  const hindiDigitRange = [2406, 2415]; // Decimal code point range for Hindi digits

  if (code >= englishDigitRange[0] && code <= englishDigitRange[1]) {
    return "EnglishDigit";
  } else if (code >= hindiDigitRange[0] && code <= hindiDigitRange[1]) {
    return "HindiDigit";
  } else {
    return "Not a digit or unrecognized";
  }

}
