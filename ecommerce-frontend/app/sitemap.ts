import { MetadataRoute } from 'next';

export default async function sitemap(): Promise<MetadataRoute.Sitemap> {
  const baseUrl = 'https://yourdomain.com';

    let products = [];
      try {
          const res = await fetch(`${process.env.NEXT_PUBLIC_API_URL}/products`);
              const data = await res.json();
                  products = data.data || [];
                    } catch (error) {
                        console.error('Sitemap fetch error:', error);
                          }

                            const productUrls = products.map((product: any) => ({
                                url: `${baseUrl}/products/${product.slug}`,
                                    lastModified: new Date(product.updated_at || Date.now()),
                                        changeFrequency: 'daily' as const,
                                            priority: 0.8,
                                              }));

                                                return [
                                                    {
                                                          url: baseUrl,
                                                                lastModified: new Date(),
                                                                      changeFrequency: 'always',
                                                                            priority: 1.0,
                                                                                },
                                                                                    ...productUrls,
                                                                                      ];
                                                                                      }
                                                                                      