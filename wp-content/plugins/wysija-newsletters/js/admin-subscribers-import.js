jQuery(function(a){a("tr.csvmode").fadeOut();a('input[name="wysija[import][type]"]').click(function(){a(".form-valid").validationEngine("hide");a("tr.csvmode").fadeOut();if(this.value=="copy"){a("tr.csvmode.copy").fadeIn()}else{a("tr.csvmode.upload").fadeIn()}});a("#copy-paste").click()});