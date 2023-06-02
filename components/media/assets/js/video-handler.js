

/**
 * @typedef VideoConfiguration
 * @property {string} id
 */

class VideoHandler extends BaseHandler {
  id;
  src;
  mime;

  details;

  static registry = {};

  static getHandler(key, def = null) {
    if (this.registry.hasOwnProperty(key))
      return this.registry[key];
    return def;
  }

  /**
   * 
   * @param {VideoConfiguration} config 
   */
  static async init(config) {
    const res = await BaseHandler.getMedia(config.id);

    if (!res) return null;

    const handler = new VideoHandler();

    const { id, url, ...details } = res;

    handler.id = id;
    handler.src = url;
    handler.mime = details.mime;
    handler.details = details;

    this.registry[id] = handler;
    return handler;
  }


  static getHandler(key, def = null) {
    if (this.registry.hasOwnProperty(key))
      return this.registry[key];
    return def;
  }


  get container() {
    return $(".media-video-container#media-video-handler");
  }



  initialize() {
    $("#media-video-handler video").attr({
      src: this.src,
      type: this.mime
    });
  }
}