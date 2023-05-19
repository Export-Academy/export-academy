/**
 * @class Question
 */
class Question {
  prefix = "";


  get builderContainer() {
    return $("#" + this.prefix + "question-builder-container");
  }

  get questionTypeButton() {
    return $("button." + this.prefix + "question-type");
  }

  static start(prefix = null) {
    const instance = new Question();

    if (prefix)
      instance.prefix = prefix;

    instance.questionTypeButton.on("click", async function (e) {
      const target = e.target;
      const name = $(target).html();
      const currentType = $("#" + instance.prefix + "current-question-type").html();

      if (name === currentType) return;

      $("#" + instance.prefix + "current-question-type").html(name);

      const type = $(target).data("type");
      $("#" + instance.prefix + "question-type-input").val(type);

      const content = await AdminController.fetch("component", "question_build", {
        type
      });

      $("#" + instance.prefix + "question-builder-container").html(content);
      feather.replace();
    });
  }
}



