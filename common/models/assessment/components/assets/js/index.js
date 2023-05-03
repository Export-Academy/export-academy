

class Question {
  _builder;

  set builder(html) {
    this._builder = html;
  }

  get builder() {
    return this._builder;
  }

  initialize(options) {
    console.log(options);
  }
}

class Checkbox extends Question {
}


class MultipleChoice extends Question {

}

class Dropdown extends Question {

}

class Ranking extends Question {


}


