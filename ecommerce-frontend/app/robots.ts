import { MetadataRoute } from 'next';

export default function robots(): MetadataRoute.Robots {
  return {
      rules: [
            {
                    userAgent: '*',
                            allow: '/',
                                  },
                                        {
                                                userAgent: ['GPTBot', 'PerplexityBot', 'Google-Extended'],
                                                        allow: '/',
                                                              },
                                                                  ],
                                                                      sitemap: 'https://yourdomain.com/sitemap.xml',
                                                                        };
                                                                        }
                                                                        