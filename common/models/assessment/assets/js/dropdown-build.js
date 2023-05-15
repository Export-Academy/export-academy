Dropdown = class {

  static get removeOptionButton() {
    return $("button.remove-option");
  }

  static get addOptionButton() {
    return $("#add-dropdown-option");
  }

  static get optionsContainer() {
    return $("#dropdown-container");
  }

  static initialize() {
    Dropdown.addOptionButton.on("click", Dropdown.handleAddOption);
    Dropdown.onUpdate();
  }

  static onUpdate() {
    Dropdown.removeOptionButton.on("click", Dropdown.handleRemoveOption);
    if (Dropdown.removeOptionButton.length === 1) {
      Dropdown.removeOptionButton.addClass("disabled");
    } else {
      Dropdown.removeOptionButton.removeClass("disabled");
    }
    feather.replace();
  }

  static handleRemoveOption(e) {
    const target = $(e.currentTarget);
    target.parent().remove();
    Dropdown.onUpdate();
  }

  static async handleAddOption() {
    const count = Dropdown.removeOptionButton.length + 1;
    const content = await AdminController.fetch("component", "render", {
      name: "dropdown-option",
      handler: "common\\models\\assessment\\Dropdown",
      params: {
        option: count
      }
    });
    Dropdown.optionsContainer.append(content);
    Dropdown.onUpdate();
  }
}


Dropdown.initialize();

