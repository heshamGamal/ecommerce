export interface Product {
      id: number;
        name: string;
          slug: string;
            price: number;
              description: string;
                short_description?: string;
                  main_image: string;
                    images?: string[];
                      stock: number;
                        sku?: string;
                          rating_avg?: number;
                            reviews_count?: number;
                              faqs?: { question: string; answer: string }[];
                              }

                              export interface CartItem {
                                product: Product;
                                  quantity: number;
                                  }

                                  export interface User {
                                    id: number;
                                      name: string;
                                        email: string;
                                        }

