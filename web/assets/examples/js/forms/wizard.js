(function (global, factory) {
  if (typeof define === "function" && define.amd) {
    define('/forms/wizard', ['jquery', 'Site'], factory);
  } else if (typeof exports !== "undefined") {
    factory(require('jquery'), require('Site'));
  } else {
    var mod = {
      exports: {}
    };
    factory(global.jQuery, global.Site);
    global.formsWizard = mod.exports;
  }
})(this, function (_jquery, _Site) {
  'use strict';

  var _jquery2 = babelHelpers.interopRequireDefault(_jquery);

  (0, _jquery2.default)(document).ready(function ($$$1) {
    (0, _Site.run)();
  });

  // Wizard Form
  // -------------------
  (function () {
    // set up formvalidation
    (0, _jquery2.default)('#VehiculeForm').formValidation({
      framework: 'bootstrap',
      fields: {
        immatriculation: {
          validators: {
            notEmpty: {
              message: 'Immatriculation requis'
            }
          }
        },
        marque: {
          validators: {
            notEmpty: {
              message: 'Marque du vehicule requis'
            }
          }
        },
        modele: {
            validators: {
                notEmpty: {
                    message: 'Modèle du vehicule requis'
                }
            }
        }
      },
      err: {
        clazz: 'text-help'
      },
      row: {
        invalid: 'has-danger'
      }
    });

    (0, _jquery2.default)("#conducteurForm").formValidation({
      framework: 'bootstrap',
      fields: {
        nom: {
          validators: {
            notEmpty: {
              message: 'Nom requis'
            }
          }
        },
        prenom: {
          validators: {
            notEmpty: {
              message: 'Prénom requis'
            }
          }
        },
        telephone: {
              validators: {
                  notEmpty: {
                      message: 'Numéro téléphone requis'
                  },
                  regexp: {
                      regexp: /^(0|\+33)[1-9]([-. ]?[0-9]{2}){4}$/,
                      message: 'Veuillez entrer un numéro de téléphone valide'
                  }
              }
         },
         email: {
              validators: {
                  notEmpty: {
                      message: 'Adresse email requis'
                  },
                  emailAddress: {
                      message: 'Veuillez entrer un adresse email valide'
                  }
              }
         },
         date_naissance: {
             validators: {
                 notEmpty: {
                     message: 'Date de naissance requis'
                 }
             }
         }
      },
      err: {
        clazz: 'text-help'
      },
      row: {
        invalid: 'has-danger'
      }
    });

      (0, _jquery2.default)("#adresseForm").formValidation({
          framework: 'bootstrap',
          fields: {
              numero_rue: {
                  validators: {
                      notEmpty: {
                          message: 'Champ requiss'
                      } }
              },
              code_postal: {
                  validators: {
                      notEmpty: {
                          message: 'Code postal requis'
                      },
                      regexp: {
                          regexp: /^[0-9]{5}$/,
                          message: 'Veuillez entrer un code postal valide'
                      }
                  }
              }
          },
          err: {
              clazz: 'text-help'
          },
          row: {
              invalid: 'has-danger'
          }
      });

    // init the wizard
    var defaults = Plugin.getDefaults("wizard");
    var options = _jquery2.default.extend(true, {}, defaults, {
      buttonsAppendTo: '.panel-body'
    });

    var wizard = (0, _jquery2.default)("#generateContrat").wizard(options).data('wizard');

    // setup validator
    // http://formvalidation.io/api/#is-valid
    wizard.get("#voiture_sec").setValidator(function () {
      var fv = (0, _jquery2.default)("#VehiculeForm").data('formValidation');
      fv.validate();

      if (!fv.isValid()) {
        return false;
      }

      return true;
    });

    wizard.get("#conducteur_sec").setValidator(function () {
      var fv = (0, _jquery2.default)("#conducteurForm").data('formValidation');
      fv.validate();

      if (!fv.isValid()) {
        return false;
      }

      return true;
    });

      wizard.get("#adresse_sec").setValidator(function () {
          var fv = (0, _jquery2.default)("#adresseForm").data('formValidation');
          fv.validate();

          if (!fv.isValid()) {
              return false;
          }

          return true;
      });
  })();

  // -----------------------------
  // http://formvalidation.io/api/#is-valid-container
  (function () {
    var defaults = Plugin.getDefaults("wizard");
    var options = _jquery2.default.extend(true, {}, defaults, {
      onInit: function onInit() {
        (0, _jquery2.default)('#exampleFormContainer').formValidation({
          framework: 'bootstrap',
          fields: {
            username: {
              validators: {
                notEmpty: {
                  message: 'The username is required'
                }
              }
            },
            password: {
              validators: {
                notEmpty: {
                  message: 'The password is required'
                }
              }
            },
            number: {
              validators: {
                notEmpty: {
                  message: 'The credit card number is not valid'
                }
              }
            },
            cvv: {
              validators: {
                notEmpty: {
                  message: 'The CVV number is required'
                }
              }
            }
          },
          err: {
            clazz: 'text-help'
          },
          row: {
            invalid: 'has-danger'
          }
        });
      },
      validator: function validator() {
        var fv = (0, _jquery2.default)('#exampleFormContainer').data('formValidation');

        var $this = (0, _jquery2.default)(this);

        // Validate the container
        fv.validateContainer($this);

        var isValidStep = fv.isValidContainer($this);
        if (isValidStep === false || isValidStep === null) {
          return false;
        }

        return true;
      },
      onFinish: function onFinish() {
        // $('#exampleFormContainer').submit();
      },
      buttonsAppendTo: '.panel-body'
    });

    (0, _jquery2.default)("#exampleWizardFormContainer").wizard(options);
  })();

  // Wizard Pager
  // --------------------------
  (function () {
    var defaults = Plugin.getDefaults("wizard");

    var options = _jquery2.default.extend(true, {}, defaults, {
      step: '.wizard-pane',
      templates: {
        buttons: function buttons() {
          var options = this.options;
          var html = '<div class="btn-group btn-group-sm">' + '<a class="btn btn-default btn-outline" href="#' + this.id + '" data-wizard="back" role="button">' + options.buttonLabels.back + '</a>' + '<a class="btn btn-success btn-outline float-right" href="#' + this.id + '" data-wizard="finish" role="button">' + options.buttonLabels.finish + '</a>' + '<a class="btn btn-default btn-outline float-right" href="#' + this.id + '" data-wizard="next" role="button">' + options.buttonLabels.next + '</a>' + '</div>';
          return html;
        }
      },
      buttonLabels: {
        next: '<i class="icon wb-chevron-right" aria-hidden="true"></i>',
        back: '<i class="icon wb-chevron-left" aria-hidden="true"></i>',
        finish: '<i class="icon wb-check" aria-hidden="true"></i>'
      },

      buttonsAppendTo: '.panel-actions'
    });

    (0, _jquery2.default)("#exampleWizardPager").wizard(options);
  })();

  // Example Wizard Progressbar
  // --------------------------
  (function () {
    var defaults = Plugin.getDefaults("wizard");

    var options = _jquery2.default.extend(true, {}, defaults, {
      step: '.wizard-pane',
      onInit: function onInit() {
        this.$progressbar = this.$element.find('.progress-bar').addClass('progress-bar-striped');
      },
      onBeforeShow: function onBeforeShow(step) {
        step.$element.tab('show');
      },
      onFinish: function onFinish() {
        this.$progressbar.removeClass('progress-bar-striped').addClass('progress-bar-success');
      },
      onAfterChange: function onAfterChange(prev, step) {
        var total = this.length();
        var current = step.index + 1;
        var percent = current / total * 100;

        this.$progressbar.css({
          width: percent + '%'
        }).find('.sr-only').text(current + '/' + total);
      },
      buttonsAppendTo: '.panel-body'
    });

    (0, _jquery2.default)("#exampleWizardProgressbar").wizard(options);
  })();

  // Example Wizard Tabs
  // -------------------
  (function () {
    var defaults = Plugin.getDefaults("wizard");
    var options = _jquery2.default.extend(true, {}, defaults, {
      step: '> .nav > li > a',
      onBeforeShow: function onBeforeShow(step) {
        step.$element.tab('show');
      },
      classes: {
        step: {
          //done: 'color-done',
          error: 'color-error'
        }
      },
      onFinish: function onFinish() {
        alert('finish');
      },
      buttonsAppendTo: '.tab-content'
    });

    (0, _jquery2.default)("#exampleWizardTabs").wizard(options);
  })();

  // Example Wizard Accordion
  // ------------------------
  (function () {
    var defaults = Plugin.getDefaults("wizard");
    var options = _jquery2.default.extend(true, {}, defaults, {
      step: '.panel-title[data-toggle="collapse"]',
      classes: {
        step: {
          //done: 'color-done',
          error: 'color-error'
        }
      },
      templates: {
        buttons: function buttons() {
          return '<div class="panel-footer">' + defaults.templates.buttons.call(this) + '</div>';
        }
      },
      onBeforeShow: function onBeforeShow(step) {
        step.$pane.collapse('show');
      },

      onBeforeHide: function onBeforeHide(step) {
        step.$pane.collapse('hide');
      },

      onFinish: function onFinish() {
        alert('finish');
      },

      buttonsAppendTo: '.panel-collapse'
    });

    (0, _jquery2.default)("#exampleWizardAccordion").wizard(options);
  })();
});