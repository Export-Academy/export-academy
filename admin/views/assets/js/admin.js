
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

  static async fetch(controller, method = null, data = null, post = null, options = {}) {
    const url = new URL(`${this.base + controller}/${method ?? ""}`);

    if (!post) {
      post = new FormData();
      this.parseData(data ?? {}, post);
    }


    try {
      const res = await fetch(url.href, { method: "POST", body: post, ...options });
      if (res.status === 200) {

        const type = res.headers.get("Content-Type");

        if (type === "application/json") return await res.json();
        return await res.text();
      }
      console.log("Something went wrong status " + res.status);
      return null;
    } catch (error) {
      console.log(error)
      return null;
    }
  }
}