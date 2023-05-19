Checkbox = class {

  prefix = "";

  get removeOptionButton() {
    return $("button." + this.prefix + "remove-option");
  }

  get addOptionButton() {
    return $("#" + this.prefix + "add-checkbox-option");
  }

  get optionsContainer() {
    return $("#" + this.prefix + "checkbox-container");
  }

  static initialize(prefix = null) {
    const instance = new Checkbox();

    if (prefix)
      instance.definePrefix(prefix);

    instance.addOptionButton.on("click", () => instance.handleAddOption(instance));
    instance.onUpdate();

    return instance;
  }


  definePrefix(prefix) {
    this.prefix = prefix;
  }

  onUpdate() {
    const context = this;
    this.removeOptionButton.on("click", (e) => context.handleRemoveOption(e, context));
    if (this.removeOptionButton.length === 1) {
      this.removeOptionButton.addClass("disabled");
    } else {
      this.removeOptionButton.removeClass("disabled");
    }
    feather.replace();
  }

  handleRemoveOption(e, context) {
    const target = $(e.currentTarget);
    target.parent().remove();
    context.onUpdate();
  }

  async handleAddOption(context) {
    const count = context.removeOptionButton.length + 1;
    const content = await AdminController.fetch("component", "render", {
      name: "checkbox-option",
      handler: "common\\models\\assessment\\Checkboxes",
      params: {
        option: count,
        prefix: context.prefix ?? null
      }
    });
    context.optionsContainer.append(content);
    context.onUpdate();
  }
}


