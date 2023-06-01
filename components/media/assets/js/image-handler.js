

/**
 * @typedef ImageConfiguration
 * @property {string} id
 */

class ImageHandler extends BaseHandler {

  src;
  static registry = {};


  static getHandler(key, def = null) {
    if (this.registry.hasOwnProperty(key))
      return this.registry[key];
    return def;
  }

  /**
   * 
   * @param {ImageConfiguration} config 
   */
  static async init(config) {
    const res = await BaseHandler.getMedia(config.id);

    if (!res) return null;

    const handler = new ImageHandler();

    const { id, url } = res;

    handler.src = url;
    this.registry[id] = handler;

    return handler;
  }


  get container() {
    return $(".media-image-container#media-image-handler");
  }

  initialize() {
    $(".media-image-container#media-image-handler img").attr({
      src: this.src
    });
  }
}