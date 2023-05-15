Checkbox = class {
  static get removeOptionButton() {
    return $("button.remove-option");
  }

  static get addOptionButton() {
    return $("#add-checkbox-option");
  }

  static get optionsContainer() {
    return $("#checkbox-container");
  }

  static initialize() {
    Checkbox.addOptionButton.on("click", Checkbox.handleAddOption);
    Checkbox.onUpdate();
  }

  static onUpdate() {
    Checkbox.removeOptionButton.on("click", Checkbox.handleRemoveOption);
    if (Checkbox.removeOptionButton.length === 1) {
      Checkbox.removeOptionButton.addClass("disabled");
    } else {
      Checkbox.removeOptionButton.removeClass("disabled");
    }
    feather.replace();
  }

  static handleRemoveOption(e) {
    const target = $(e.currentTarget);
    target.parent().remove();
    Checkbox.onUpdate();
  }

  static async handleAddOption() {
    const count = Checkbox.removeOptionButton.length + 1;
    const content = await AdminController.fetch("component", "render", {
      name: "checkbox-option",
      handler: "common\\models\\assessment\\Checkboxes",
      params: {
        option: count
      }
    });
    Checkbox.optionsContainer.append(content);
    Checkbox.onUpdate();
  }
}

Checkbox.initialize();


