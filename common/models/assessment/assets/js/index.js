/**
 * @class Question
 */
class Question {
  static get builderContainer() {
    return $("#question-builder-container");
  }

  static get questionTypeButton() {
    return $("button.question-type");
  }

  static start() {
    this.questionTypeButton.on("click", async function (e) {
      const target = e.target;
      const name = $(target).html();
      const currentType = $("#current-question-type").html();

      if (name === currentType) return;

      $("#current-question-type").html(name);

      const type = $(target).data("type");
      $("#question-type-input").val(type);
      const content = await AdminController.fetch("component", "question_build", {
        type
      });

      $("#question-builder-container").html(content);
      feather.replace();
    });
  }
}



