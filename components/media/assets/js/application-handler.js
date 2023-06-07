/**
 * @typedef HtmlConfiguration
 * @property {string} id
 */

class ApplicationHandler extends BaseHandler {
  src;
  details;

  static registry = {};

  static getHandler(key, def = null) {
    if (this.registry.hasOwnProperty(key))
      return this.registry[key];
    return def;
  }

  /**
   * 
   * @param {HtmlConfiguration} config 
   * @returns 
   */
  static async init(config) {
    const res = await BaseHandler.getMedia(config.id);

    if (!res) return null;

    const handler = new ApplicationHandler();

    const { id, url, ...details } = res;

    handler.src = url;
    handler.details = details;
    this.registry[id] = handler;

    return handler;
  }


  get container() {
    return $(".media-app-container#media-app-handler");
  }


  initialize() {
    $(".media-app-container#media-app-handler embed").attr({
      src: this.src
    });

    $(".media-app-container#media-app-handler a").attr({
      href: this.src
    });
  }
}