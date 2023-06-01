class BaseHandler {

  static async getMedia(id) {
    const res = await AdminController.fetch("media", "media", { media: id });
    return res;
  }


  get container() {
    throw new Error("container() getter must be implemented");
  }
}
