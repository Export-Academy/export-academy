
class AdminController {
  static base = `${window.location.origin}/academy/admin/`;

  /**
   * 
   * @param {} data 
   * @param {FormData} post 
   * @param {} name 
   */
  static parseData(data, post, name = null) {
    const entries = Object.entries(data);

    for (let index = 0; index < entries.length; index++) {


      const [_name, _value] = entries[index];


      if (typeof _value === "object") {

        this.parseData(_value, post, function (__name) {
          return `${_name}[${__name}]`;
        });

      } else {

        post.append(name ? name(_name) : _name, _value);

      }
    }
  }

  static async fetch(controller, method = null, data = {}) {
    const url = new URL(`${this.base + controller}/${method ?? ""}`);

    const post = new FormData();

    this.parseData(data, post);

    try {
      const res = await fetch(url.href, { method: "POST", body: post });
      if (res.status === 200) {

        const content = await res.text();

        if (content.length === 0) return await res.json();
        return content;


      }
      console.log("Something went wrong status " + res.status);
      return null;
    } catch (error) {
      console.log(error)
      return null;
    }
  }
}