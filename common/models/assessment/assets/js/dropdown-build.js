Dropdown = class {
  prefix = "";

  static initialize(prefix = null) {
    const instance = new Dropdown();

    if (prefix)
      instance.definePrefix(prefix)

    instance.addOptionButton.on("click", () => instance.handleAddOption(instance));
    instance.onUpdate();

    return instance;
  }

  definePrefix(prefix) {
    this.prefix = prefix;
  }


  get removeOptionButton() {
    return $("button." + this.prefix + "remove-option");
  }

  get addOptionButton() {
    return $("#" + this.prefix + "add-dropdown-option");
  }

  get optionsContainer() {
    return $("#" + this.prefix + "dropdown-container");
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
      name: "dropdown-option",
      handler: "common\\models\\assessment\\Dropdown",
      params: {
        option: count,
        prefix: context.prefix ?? null
      }
    });

    context.optionsContainer.append(content);
    context.onUpdate();
  }
}

